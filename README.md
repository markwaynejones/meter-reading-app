# Meter Reading Management App

## Overview
This Meter Reading Management App allows users to manage utility meters(gas/electric) along
with meter readings for each meter.

It also provides estimation readings for future meter reading dates along with the ability
to bulk upload meter reading values via CSV uploads.

## Features
- CRUD operations for meter and meter readings (only Creating and Reading)
- Bulk CSV upload for meter readings.
- Validation of CSV data with error reporting.
- Support for estimated readings.
- Validation to ensure that the submitted reading value falls within 25% of the estimated consumption value
- Email notifications for invalid meter readings.
- Queued jobs for processing large CSV uploads.

## Usage

### View all Meters
1. Navigate to the Meters listing page. (**root of site**)

### Add a Meter
1. Navigate to the Meters listing page. (**root of site**)
2. Click **Add Meter**.
3. Fill in the form and submit.

## Add a Meter Validation Rules
- **Estimated Annual Consumption:** Must be between `2000` and `8000`.

### Add a Meter Reading
1. Navigate to the Meters listing page. (**root of site**)
2. Click **View** under the Actions column
3. Fill in the reading value and date of the Add Reading form and submit 
(leave the value blank for an estimation)

## Add a Meter Reading Validation Rules
- **Reading Value:** Must be within 25% of estimated consumption value

### Upload Bulk Readings
1. Navigate to the Meters listing page. (**root of site**)
2. Click the **Bulk Upload** button on the Meters listing page.
3. Upload a CSV file in the specified format:
   ```csv
   mpxn,reading_date,reading_value
   ELEC1234,15/07/2024,7000
   GAS5678,29/07/2024,3500
   ```
4. Click **Upload CSV**. The system will process the file and notify you of 
any errors via email.

## CSV Bulk Upload Validation Rules
- **Reading Date:** Must be a valid date in `d/m/Y` format.
- **Meter Validation:** The meter must exist in the database.

## Queued Jobs
The app uses Laravel's queue system for processing CSV uploads.

### Notifications
- Invalid rows in bulk uploads are emailed to a predefined address.

## Testing
Tests have been added for certain features

## Possible Future Enhancements
- Include edit and delete meter and meter reading functionality
- Add more tests
- Improved validation on adding single meter readings to ensure
readings added must always be have a reading date after the last
reading date
- Use Livewire for the frontend rather than blade
- Use a package to help handle CSV upload such as league/csv
- Implement user authentication and roles (for example only certain roles can bulk upload).
- Add reporting and analytics for meter readings.
- Improve CSV upload processing by splitting large CSVs into smaller files
- Instead of emailing bulk upload errors, we could use websockets and realtime notifications
