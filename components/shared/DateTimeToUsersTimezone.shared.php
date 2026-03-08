<?php
function DateTimeToUsersTimezone($original_date_given) {
    if (empty($original_date_given)) {
        return '-';
    }
    
    try {
        // Parse "MM/DD/YYYY HH:mm"
        $parts = explode(' ', $original_date_given);
        if (count($parts) < 2) {
            return date('H:i', strtotime($original_date_given));
        }
        
        $datePart = $parts[0];
        $timePart = $parts[1];
        
        $dateParts = explode('/', $datePart);
        if (count($dateParts) < 3) {
            return date('H:i', strtotime($original_date_given));
        }
        
        $month = intval($dateParts[0]);
        $day = intval($dateParts[1]);
        $year = intval($dateParts[2]);
        
        $timeParts = explode(':', $timePart);
        $hour = intval($timeParts[0]);
        $minute = intval($timeParts[1]);
        
        // Create DateTime in Europe/Berlin timezone (server time)
        $serverDate = new DateTime();
        $serverDate->setTimezone(new DateTimeZone('Europe/Berlin'));
        $serverDate->setDate($year, $month, $day);
        $serverDate->setTime($hour, $minute);
        
        // Convert to user's timezone (default to Europe/Berlin if can't detect)
        try {
            $userTimezone = new DateTimeZone(date_default_timezone_get());
        } catch (Exception $e) {
            $userTimezone = new DateTimeZone('Europe/Berlin');
        }
        
        $serverDate->setTimezone($userTimezone);
        
        // Format as "HH:MM"
        return $serverDate->format('H:i');
        
    } catch (Exception $e) {
        // Fallback to simple time extraction
        return date('H:i', strtotime($original_date_given));
    }
}
?>