# Feature Implementation Plan

## Overview
This document outlines the implementation plan for four new features:
1. Search Functionality
2. Audit Logging
3. Email Notifications
4. Export Feature

---

## 1. Search Functionality

### Files to Create/Modify

#### New Files
- None (add to existing controllers)

#### Files to Modify
- `app/Http/Controllers/GuestController.php` - Add search method
- `app/Http/Controllers/ReservationController.php` - Add search method
- `app/Http/Controllers/RoomController.php` - Add search method
- `resources/views/guests/index.blade.php` - Add search form
- `resources/views/reservations/index.blade.php` - Add search form
- `resources/views/rooms/index.blade.php` - Add search form
- `routes/web.php` - Add search routes

### Implementation Details

#### Guest Search
```php
// GuestController.php
public function search(Request $request)
{
    $query = $request->get('q');
    $guests = Guest::where('full_name', 'LIKE', "%{$query}%")
        ->orWhere('email', 'LIKE', "%{$query}%")
        ->orWhere('phone', 'LIKE', "%{$query}%")
        ->orderBy('created_at', 'desc')
        ->paginate(20);
    
    return view('guests.index', compact('guests', 'query'));
}
```

#### Reservation Search
```php
// ReservationController.php
public function search(Request $request)
{
    $query = $request->get('q');
    $reservations = Reservation::with(['guest', 'room.roomType'])
        ->whereHas('guest', function($q) use ($query) {
            $q->where('full_name', 'LIKE', "%{$query}%");
        })
        ->orWhere('reservation_id', 'LIKE', "%{$query}%")
        ->orderBy('created_at', 'desc')
        ->paginate(20);
    
    return view('reservations.index', compact('reservations', 'query'));
}
```

#### Room Search
```php
// RoomController.php
public function search(Request $request)
{
    $query = $request->get('q');
    $rooms = Room::with('roomType')
        ->where('room_number', 'LIKE', "%{$query}%")
        ->orWhere('status', 'LIKE', "%{$query}%")
        ->orderBy('room_number')
        ->paginate(20);
    
    return view('rooms.index', compact('rooms', 'query'));
}
```

---

## 2. Audit Logging

### Files to Create/Modify

#### New Files
- `database/migrations/xxxx_xx_xx_create_audit_logs_table.php`
- `app/Models/AuditLog.php`
- `app/Services/AuditService.php`
- `resources/views/audit-logs/index.blade.php`
- `app/Http/Controllers/AuditLogController.php`

#### Files to Modify
- `routes/web.php` - Add audit log routes
- All Controllers - Add audit logging calls

### Migration Schema
```php
Schema::create('audit_logs', function (Blueprint $table) {
    $table->id('log_id');
    $table->foreignId('user_id')->nullable()->constrained('users', 'user_id');
    $table->string('action'); // create, update, delete, check_in, check_out, etc.
    $table->string('model_type'); // Guest, Reservation, Room, Payment, User
    $table->unsignedBigInteger('model_id')->nullable();
    $table->json('old_values')->nullable();
    $table->json('new_values')->nullable();
    $table->string('ip_address')->nullable();
    $table->string('user_agent')->nullable();
    $table->timestamps();
});
```

### AuditService
```php
class AuditService
{
    public static function log($action, $model, $oldValues = null, $newValues = null)
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => get_class($model),
            'model_id' => $model->getKey(),
            'old_values' => $oldValues ? json_encode($oldValues) : null,
            'new_values' => $newValues ? json_encode($newValues) : null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
```

---

## 3. Email Notifications

### Files to Create/Modify

#### New Files
- `app/Mail/ReservationConfirmation.php`
- `app/Mail/ReservationCancelled.php`
- `app/Mail/CheckInConfirmation.php`
- `app/Mail/CheckOutConfirmation.php`
- `resources/views/emails/reservations/confirmation.blade.php`
- `resources/views/emails/reservations/cancelled.blade.php`
- `resources/views/emails/reservations/check-in.blade.php`
- `resources/views/emails/reservations/check-out.blade.php`

#### Files to Modify
- `app/Http/Controllers/ReservationController.php` - Add email sending
- `.env` - Configure mail settings

### Mailable Class Example
```php
class ReservationConfirmation extends Mailable
{
    public $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function build()
    {
        return $this->subject('Reservation Confirmation - Luxe Stay Hotel')
            ->view('emails.reservations.confirmation');
    }
}
```

### Email Template Example
```blade
<!DOCTYPE html>
<html>
<head>
    <title>Reservation Confirmation</title>
</head>
<body>
    <h1>Reservation Confirmed!</h1>
    <p>Dear {{ $reservation->guest->full_name }},</p>
    <p>Your reservation has been confirmed.</p>
    <h2>Reservation Details</h2>
    <ul>
        <li>Reservation ID: #{{ $reservation->reservation_id }}</li>
        <li>Room: {{ $reservation->room->room_number }}</li>
        <li>Check-in: {{ $reservation->check_in->format('M d, Y') }}</li>
        <li>Check-out: {{ $reservation->check_out->format('M d, Y') }}</li>
        <li>Total: ${{ number_format($reservation->total_price, 2) }}</li>
    </ul>
</body>
</html>
```

---

## 4. Export Feature

### Files to Create/Modify

#### New Files
- `app/Exports/ReservationsExport.php`
- `app/Exports/GuestsExport.php`
- `app/Exports/PaymentsExport.php`
- `app/Http/Controllers/ExportController.php`

#### Files to Modify
- `composer.json` - Add maatwebsite/excel package
- `routes/web.php` - Add export routes
- `resources/views/reservations/index.blade.php` - Add export button
- `resources/views/guests/index.blade.php` - Add export button
- `resources/views/payments/index.blade.php` - Add export button

### Package Installation
```bash
composer require maatwebsite/excel
```

### Export Class Example
```php
class ReservationsExport implements FromQuery, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        return Reservation::with(['guest', 'room.roomType'])
            ->when($this->filters['status'] ?? null, function($q, $status) {
                $q->where('status', $status);
            })
            ->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'ID',
            'Guest Name',
            'Room Number',
            'Room Type',
            'Check-In',
            'Check-Out',
            'Total Price',
            'Status',
            'Created At'
        ];
    }

    public function map($reservation): array
    {
        return [
            $reservation->reservation_id,
            $reservation->guest->full_name,
            $reservation->room->room_number,
            $reservation->room->roomType->type_name,
            $reservation->check_in->format('Y-m-d'),
            $reservation->check_out->format('Y-m-d'),
            $reservation->total_price,
            $reservation->status,
            $reservation->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
```

### Export Controller
```php
class ExportController extends Controller
{
    public function reservations(Request $request)
    {
        $filters = $request->only(['status', 'date_from', 'date_to']);
        return Excel::download(
            new ReservationsExport($filters),
            'reservations_' . date('Y-m-d') . '.xlsx'
        );
    }

    public function reservationsPdf(Request $request)
    {
        $reservations = Reservation::with(['guest', 'room.roomType'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $pdf = Pdf::loadView('exports.reservations-pdf', compact('reservations'));
        return $pdf->download('reservations_' . date('Y-m-d') . '.pdf');
    }
}
```

---

## Routes to Add

```php
// Search Routes
Route::get('/guests/search', [GuestController::class, 'search'])->name('guests.search');
Route::get('/reservations/search', [ReservationController::class, 'search'])->name('reservations.search');
Route::get('/rooms/search', [RoomController::class, 'search'])->name('rooms.search');

// Audit Log Routes (Admin only)
Route::prefix('audit-logs')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AuditLogController::class, 'index'])->name('audit-logs.index');
    Route::get('/{id}', [AuditLogController::class, 'show'])->name('audit-logs.show');
});

// Export Routes
Route::prefix('export')->middleware(['auth', 'staff'])->group(function () {
    Route::get('/reservations', [ExportController::class, 'reservations'])->name('export.reservations');
    Route::get('/reservations/pdf', [ExportController::class, 'reservationsPdf'])->name('export.reservations.pdf');
    Route::get('/guests', [ExportController::class, 'guests'])->name('export.guests');
    Route::get('/payments', [ExportController::class, 'payments'])->name('export.payments');
});
```

---

## Implementation Order

1. **Search Functionality** (Simplest - no new dependencies)
   - Add search methods to controllers
   - Update index views with search forms
   - Add routes

2. **Audit Logging** (No external dependencies)
   - Create migration and model
   - Create AuditService
   - Add logging to controllers
   - Create admin view for logs

3. **Export Feature** (Requires package installation)
   - Install maatwebsite/excel
   - Create export classes
   - Create export controller
   - Add export buttons to views

4. **Email Notifications** (Requires mail configuration)
   - Create mailable classes
   - Create email templates
   - Add email sending to controllers
   - Configure mail settings

---

## Testing Checklist

### Search
- [ ] Search guests by name
- [ ] Search guests by email
- [ ] Search reservations by guest name
- [ ] Search reservations by ID
- [ ] Search rooms by number
- [ ] Empty search returns all results

### Audit Logging
- [ ] Log guest creation
- [ ] Log guest update
- [ ] Log guest deletion
- [ ] Log reservation creation
- [ ] Log check-in
- [ ] Log check-out
- [ ] Log cancellation
- [ ] Log payment creation
- [ ] Admin can view logs

### Email Notifications
- [ ] Send confirmation on reservation create
- [ ] Send notification on check-in
- [ ] Send notification on check-out
- [ ] Send notification on cancellation

### Export
- [ ] Export reservations to Excel
- [ ] Export reservations to PDF
- [ ] Export guests to Excel
- [ ] Export payments to Excel
