<?php

namespace Notification;


interface Notification
{
    public function main($opt): bool;

    public function getError(): string;
}