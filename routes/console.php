<?php

use Illuminate\Support\Facades\Schedule;

// Tägliches Datenbank-Backup um 02:00 Uhr, 7 Tage aufbewahren
Schedule::command('db:backup --keep=7')->dailyAt('02:00');
