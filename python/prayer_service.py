#!/usr/bin/env python3
"""
Ascend Prayer Service
Generates daily prayers using Claude API and fetches Scripture readings
"""

import os
import json
import requests
from datetime import datetime
from dotenv import load_dotenv
import anthropic

load_dotenv()

CLAUDE_API_KEY = os.getenv('CLAUDE_API_KEY')
BIBLE_API_KEY = os.getenv('BIBLE_API_KEY')
DB_CONFIG = {
    'host': 'localhost',
    'user': 'ascend_user',
    'password': os.getenv('DB_PASS'),
    'database': 'ascend'
}

class PrayerService:
    def __init__(self):
        self.client = anthropic.Anthropic(api_key=CLAUDE_API_KEY)

    def generate_daily_prayer(self, liturgical_info=None):
        """Generate a daily prayer using Claude API"""
        
        today = datetime.now().strftime('%B %d')
        
        prompt = f"""Generate a beautiful, short daily prayer for Catholic altar servers for {today}.
        
The prayer should:
- Be 2-3 sentences long
- Focus on service, devotion, and spiritual growth
- Be appropriate for young altar servers
- Connect to the altar server role
- Include a brief blessing

{f'Liturgical context: {liturgical_info}' if liturgical_info else ''}

Format the response as JSON with:
{{"prayer_title": "Title", "prayer_text": "The prayer text"}}"""

        message = self.client.messages.create(
            model="claude-opus-4-6",
            max_tokens=300,
            messages=[
                {"role": "user", "content": prompt}
            ]
        )

        response_text = message.content[0].text
        
        try:
            prayer_data = json.loads(response_text)
        except json.JSONDecodeError:
            prayer_data = {
                "prayer_title": "Daily Prayer",
                "prayer_text": response_text
            }

        return prayer_data

    def fetch_scripture(self, book='John', chapter=1, verse_start=1, verse_end=5):
        """Fetch scripture from Bible API"""
        
        headers = {'api-key': BIBLE_API_KEY}
        
        # Using ESV API as alternative if Bible API isn't available
        try:
            url = f"https://api.esv.org/v3/passage/text/?q={book}+{chapter}:{verse_start}-{verse_end}"
            response = requests.get(url, headers={'Authorization': f'Token {BIBLE_API_KEY}'})
            
            if response.status_code == 200:
                data = response.json()
                return {
                    'text': data.get('passages', [''])[0],
                    'reference': f"{book} {chapter}:{verse_start}-{verse_end}"
                }
        except:
            pass

        return {
            'text': 'Scripture reading unavailable',
            'reference': f"{book} {chapter}:{verse_start}-{verse_end}"
        }

    def get_daily_liturgical_info(self):
        """Get liturgical information for the day"""
        
        today = datetime.now()
        
        # Simple mapping - can be expanded
        liturgical_calendar = {
            (1, 1): "Solemnity of Mary, Mother of God",
            (2, 2): "Presentation of the Lord",
            (3, 19): "Solemnity of Saint Joseph",
            (5, 1): "Feast of Saint Joseph the Worker",
        }

        day_info = liturgical_calendar.get((today.month, today.day), "Ordinary Time")
        return day_info

def main():
    service = PrayerService()

    # Generate daily prayer
    liturgical_info = service.get_daily_liturgical_info()
    prayer = service.generate_daily_prayer(liturgical_info)
    
    print("Generated Prayer:")
    print(f"Title: {prayer['prayer_title']}")
    print(f"Text: {prayer['prayer_text']}")
    print(f"\nLiturgical Info: {liturgical_info}")

    # Fetch scripture
    scripture = service.fetch_scripture('John', 1, 1, 5)
    print(f"\nScripture ({scripture['reference']}):")
    print(scripture['text'])

if __name__ == '__main__':
    main()
