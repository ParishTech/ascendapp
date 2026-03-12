// API Client
// html/js/api-client.js

class AscendAPI {
    constructor() {
        this.baseURL = '/api';
        this.token = sessionStorage.getItem('token');
    }

    async request(endpoint, method = 'GET', body = null) {
        const url = `${this.baseURL}/${endpoint}`;
        
        const options = {
            method,
            headers: {
                'Content-Type': 'application/json'
            }
        };

        if (this.token) {
            options.headers['Authorization'] = `Bearer ${this.token}`;
        }

        if (body) {
            options.body = JSON.stringify(body);
        }

        try {
            const response = await fetch(url, options);
            const data = await response.json();

            if (!response.ok && response.status === 401) {
                sessionStorage.clear();
                window.location.href = '/html/login.php';
                return null;
            }

            return data;
        } catch (error) {
            console.error('API Error:', error);
            return null;
        }
    }

    // Auth
    async login(email, password) {
        return this.request('auth.php?action=login', 'POST', { email, password });
    }

    async register(name, email, password, role = 'server') {
        return this.request('auth.php?action=register', 'POST', { name, email, password, role });
    }

    async logout() {
        return this.request('auth.php?action=logout', 'POST');
    }

    async verifyToken() {
        return this.request('auth.php?action=verify', 'GET');
    }

    // Clock
    async clockIn(massId) {
        return this.request('clock.php?action=in', 'POST', { mass_id: massId });
    }

    async clockOut() {
        return this.request('clock.php?action=out', 'POST');
    }

    async getClockHistory(limit = 50, offset = 0) {
        return this.request(`clock.php?action=history&limit=${limit}&offset=${offset}`, 'GET');
    }

    async getCurrentClock() {
        return this.request('clock.php?action=current', 'GET');
    }

    // Performance Notes
    async addNote(massId, serverId, timeliness, demeanor, accuracy, notes = '', referralReason = null) {
        const body = {
            mass_id: massId,
            server_id: serverId,
            timeliness,
            demeanor,
            accuracy,
            notes
        };

        if (referralReason) {
            body.referral_reason = referralReason;
        }

        return this.request('notes.php?action=add', 'POST', body);
    }

    async getNotes(massId) {
        return this.request(`notes.php?action=get&mass_id=${massId}`, 'GET');
    }

    async getServerNotes(serverId = null, limit = 10) {
        const query = serverId ? `&server_id=${serverId}` : '';
        return this.request(`notes.php?action=server&limit=${limit}${query}`, 'GET');
    }

    async createReferral(serverId, reason) {
        return this.request('notes.php?action=referral', 'POST', {
            server_id: serverId,
            reason
        });
    }
}

// Global API instance
const api = new AscendAPI();
