import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';

dayjs.extend(relativeTime);

export function DateEU(value) {
    if (!value) return '—';
    return dayjs(value).format('DD.MM.YYYY HH:mm');
}

export function dateFormat(value) {
    if (!value) return '—';
    return dayjs(value).format('DD-MM-YYYY');
}

export function timeAgo(value) {
    if (!value) return '—';
    
    try {
        const now = dayjs();
        const then = dayjs(value);
        
        // If the date is invalid or in the future
        if (!then.isValid() || then.isAfter(now)) {
            return '—';
        }
        
        const diff = now.diff(then);
        const seconds = Math.floor(diff / 1000);
        const minutes = Math.floor(seconds / 60);
        const hours = Math.floor(minutes / 60);
        const days = Math.floor(hours / 24);
        const weeks = Math.floor(days / 7);
        const months = Math.floor(days / 30);
        const years = Math.floor(days / 365);

        if (seconds < 60) {
            return seconds + 's ago';
        } else if (minutes < 60) {
            return minutes + 'm ago';
        } else if (hours < 24) {
            return hours + 'h ago';
        } else if (days < 7) {
            return days + 'd ago';
        } else if (weeks < 4) {
            return weeks + 'w ago';
        } else if (months < 12) {
            return months + 'mo ago';
        } else {
            return years + 'y ago';
        }
    } catch (error) {
        console.error('Error in timeAgo:', error);
        return '—';
    }
}