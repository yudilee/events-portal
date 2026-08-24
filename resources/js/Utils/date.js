/**
 * Safely format a date string or timestamp into a human-readable format.
 * Examples:
 * - '2026-08-27' -> 'Thu, Aug 27, 2026'
 * - '2026-08-27T00:00:00.000000Z' -> 'Thu, Aug 27, 2026'
 */
export function formatDate(dateInput, format = 'medium') {
  if (!dateInput) return '';

  try {
    // Handle string inputs, extracting YYYY-MM-DD if ISO
    let cleanDateStr = String(dateInput);
    if (cleanDateStr.includes('T')) {
      cleanDateStr = cleanDateStr.split('T')[0];
    }

    // Use UTC parts to avoid timezone shift issues with date-only strings
    const parts = cleanDateStr.split('-');
    if (parts.length === 3) {
      const year = parseInt(parts[0], 10);
      const month = parseInt(parts[1], 10) - 1;
      const day = parseInt(parts[2], 10);
      const dateObj = new Date(Date.UTC(year, month, day));

      if (format === 'short') {
        return dateObj.toLocaleDateString('en-US', {
          timeZone: 'UTC',
          month: 'short',
          day: 'numeric',
          year: 'numeric',
        });
      }

      if (format === 'long') {
        return dateObj.toLocaleDateString('en-US', {
          timeZone: 'UTC',
          weekday: 'long',
          month: 'long',
          day: 'numeric',
          year: 'numeric',
        });
      }

      return dateObj.toLocaleDateString('en-US', {
        timeZone: 'UTC',
        weekday: 'short',
        month: 'short',
        day: 'numeric',
        year: 'numeric',
      });
    }

    const dateObj = new Date(dateInput);
    return dateObj.toLocaleDateString('en-US', {
      month: 'short',
      day: 'numeric',
      year: 'numeric',
    });
  } catch (e) {
    return String(dateInput);
  }
}
