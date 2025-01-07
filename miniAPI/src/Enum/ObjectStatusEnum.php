<?php
namespace App\Enum;

enum ObjectStatusEnum: string
{
    case Found = 'Found';
    case WaitingForRecovery = 'Waiting_for_recovery';
    case Recovered = 'Recovered';
}