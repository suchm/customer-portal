import { format, differenceInDays, differenceInHours, differenceInMinutes } from 'date-fns';

// Helper function to validate the date
function isValidDate(date) {
    const parsedDate = new Date(date);
    return !isNaN(parsedDate.getTime()) ? parsedDate : null;
}

// Function to format the date
function formatDate(date, dateFormat = 'dd-MM-yyyy') {
    const parsedDate = isValidDate(date);
    return parsedDate ? format(parsedDate, dateFormat) : 'Invalid date';
}

// Function to format distance between dates
function formatDistanceFromDate(date, type = null ) {
    const parsedDate = isValidDate(date);
    if (!parsedDate) return 'Invalid date';

    const now = new Date();
    const daysDifference = differenceInDays(now, parsedDate);
    const hoursDifference = differenceInHours(now, parsedDate);
    const minutesDifference = differenceInMinutes(now, parsedDate);

    let dateFormat = type === 'chats' ? 'MMM d' : 'h:mm a';

    if (daysDifference > 0) {
        return format(parsedDate, dateFormat);
    } else if (hoursDifference > 0) {
        return `${hoursDifference} hour${hoursDifference > 1 ? 's' : ''} ago`;
    } else if (minutesDifference > 0) {
        return `${minutesDifference} minute${minutesDifference > 1 ? 's' : ''} ago`;
    } else {
        return 'Just now';
    }
}

export { formatDate, formatDistanceFromDate };
