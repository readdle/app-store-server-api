<?php
declare(strict_types=1);

function printJsonSerializableEntity($entity): void
{
    foreach ($entity->jsonSerialize() as $property => $value) {
        $displayName = preg_replace_callback('/(?!^)[A-Z]/', fn (array $match) => ' ' . $match[0], ucfirst($property));

        switch (true) {
            case $value === '':
                $value = '""';
                break;

            case $value === null:
                $value = '<NULL>';
                break;
        }

        echo "$displayName: $value\n";
    }
}
