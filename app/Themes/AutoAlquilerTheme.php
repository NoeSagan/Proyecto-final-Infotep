<?php

declare(strict_types=1);

namespace App\Themes;

use DistortedFusion\BladeComponents\Contracts\ThemeContract;
use DistortedFusion\BladeComponents\Enums\ThemeVariable;
use DistortedFusion\BladeComponents\Enums\ThemeVariant;

class AutoAlquilerTheme implements ThemeContract
{
    public static function definitions(ThemeVariant $variant): array
    {
        return match ($variant) {
            ThemeVariant::LIGHT => static::light(),
            default => [],
        };
    }

    private static function light(): array
    {
        return [
            ThemeVariable::BACKGROUND->value           => '#f7f9fa',
            ThemeVariable::FOREGROUND->value           => '#0d1529',

            ThemeVariable::BACKDROP->value             => 'color-mix(in oklab, #f7f9fa 60%, transparent)',

            ThemeVariable::PRIMARY->value              => '#000000',
            ThemeVariable::PRIMARY_FOREGROUND->value   => '#ffffff',

            ThemeVariable::SECONDARY->value            => '#135bf9',
            ThemeVariable::SECONDARY_FOREGROUND->value => '#eff6ff',

            ThemeVariable::ACCENT->value               => '#000000',
            ThemeVariable::ACCENT_FOREGROUND->value    => '#ffffff',

            ThemeVariable::MUTED->value                => '#eef2f6',
            ThemeVariable::MUTED_FOREGROUND->value     => '#0d1529',

            ThemeVariable::CARD->value                 => '#ffffff',
            ThemeVariable::CARD_FOREGROUND->value      => '#0d1529',

            ThemeVariable::BORDER->value               => '#dfe5ed',
            ThemeVariable::INPUT->value                => '#ffffff',
            ThemeVariable::RING->value                 => 'color-mix(in oklab, #135bf9 30%, transparent)',

            ThemeVariable::INFO->value                 => '#0d4c62',
            ThemeVariable::INFO_FOREGROUND->value      => '#0d4c62',

            ThemeVariable::SUCCESS->value              => '#004c39',
            ThemeVariable::SUCCESS_FOREGROUND->value   => '#004c39',

            ThemeVariable::WARNING->value              => '#ff8904',
            ThemeVariable::WARNING_FOREGROUND->value   => '#421104',

            ThemeVariable::DANGER->value               => '#830c41',
            ThemeVariable::DANGER_FOREGROUND->value    => '#830c41',

            // Radios - 1rem para todo (cards, alertas, botones, inputs)
            ThemeVariable::RADIUS->value               => '1rem',
            ThemeVariable::RADIUS_INNER->value         => '1rem',
        ];
    }
}
