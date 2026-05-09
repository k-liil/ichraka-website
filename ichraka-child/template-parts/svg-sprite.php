<?php
/**
 * Sprite SVG — toutes les icônes décoratives Ichraka (direction Joyeux).
 * Inséré une fois en haut du body via header.php.
 *
 * @package Ichraka
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<svg width="0" height="0" style="position:absolute" aria-hidden="true">
    <defs>
        <symbol id="ic-sun" viewBox="0 0 64 64">
            <circle cx="32" cy="32" r="14" fill="currentColor"/>
            <g stroke="currentColor" stroke-width="3.5" stroke-linecap="round">
                <line x1="32" y1="6" x2="32" y2="14"/>
                <line x1="32" y1="50" x2="32" y2="58"/>
                <line x1="6" y1="32" x2="14" y2="32"/>
                <line x1="50" y1="32" x2="58" y2="32"/>
                <line x1="13" y1="13" x2="19" y2="19"/>
                <line x1="45" y1="45" x2="51" y2="51"/>
                <line x1="13" y1="51" x2="19" y2="45"/>
                <line x1="45" y1="19" x2="51" y2="13"/>
            </g>
        </symbol>
        <symbol id="ic-heart" viewBox="0 0 64 64">
            <path d="M32 56 C 8 40, 4 22, 16 14 C 24 9, 32 16, 32 22 C 32 16, 40 9, 48 14 C 60 22, 56 40, 32 56 Z" fill="currentColor"/>
        </symbol>
        <symbol id="ic-sparkle" viewBox="0 0 64 64">
            <path d="M32 4 L37 27 L60 32 L37 37 L32 60 L27 37 L4 32 L27 27 Z" fill="currentColor"/>
        </symbol>
        <symbol id="ic-squiggle" viewBox="0 0 120 30">
            <path d="M2 15 Q 17 0, 32 15 T 62 15 T 92 15 T 122 15" stroke="currentColor" stroke-width="4" fill="none" stroke-linecap="round"/>
        </symbol>
        <symbol id="ic-blob" viewBox="0 0 200 200">
            <path d="M100 10 C 150 10, 190 40, 190 100 C 190 150, 160 190, 100 190 C 40 190, 10 150, 10 100 C 10 50, 40 10, 100 10 Z" fill="currentColor"/>
        </symbol>
        <symbol id="ic-bag" viewBox="0 0 64 64">
            <path d="M14 22 L50 22 L48 56 L16 56 Z" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linejoin="round"/>
            <path d="M22 22 L22 18 a10 10 0 0 1 20 0 L42 22" fill="none" stroke="currentColor" stroke-width="3.5"/>
        </symbol>
        <symbol id="ic-jacket" viewBox="0 0 64 64">
            <path d="M16 18 L24 12 L32 18 L40 12 L48 18 L52 56 L12 56 Z" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linejoin="round"/>
            <line x1="32" y1="18" x2="32" y2="56" stroke="currentColor" stroke-width="3.5"/>
        </symbol>
        <symbol id="ic-glasses" viewBox="0 0 64 64">
            <circle cx="18" cy="34" r="10" fill="none" stroke="currentColor" stroke-width="3.5"/>
            <circle cx="46" cy="34" r="10" fill="none" stroke="currentColor" stroke-width="3.5"/>
            <line x1="28" y1="34" x2="36" y2="34" stroke="currentColor" stroke-width="3.5"/>
            <path d="M8 34 L4 24 M56 34 L60 24" stroke="currentColor" stroke-width="3.5" stroke-linecap="round"/>
        </symbol>
        <symbol id="ic-people" viewBox="0 0 64 64">
            <circle cx="22" cy="18" r="8" fill="currentColor"/>
            <circle cx="46" cy="22" r="6" fill="currentColor"/>
            <path d="M6 56 C 6 42, 14 36, 22 36 C 30 36, 38 42, 38 56 Z" fill="currentColor"/>
            <path d="M36 56 C 36 46, 42 42, 46 42 C 50 42, 58 46, 58 56 Z" fill="currentColor"/>
        </symbol>
        <symbol id="ic-coin" viewBox="0 0 64 64">
            <circle cx="32" cy="32" r="24" fill="currentColor"/>
            <text x="32" y="42" text-anchor="middle" font-family="serif" font-weight="700" font-size="26" fill="#14213D">د</text>
        </symbol>
        <symbol id="ic-school" viewBox="0 0 64 64">
            <path d="M8 30 L32 14 L56 30 L56 54 L8 54 Z" fill="currentColor"/>
            <rect x="26" y="36" width="12" height="18" fill="#14213D"/>
        </symbol>
        <symbol id="ic-action" viewBox="0 0 64 64">
            <circle cx="32" cy="32" r="22" fill="currentColor"/>
            <path d="M22 32 L29 39 L42 24" stroke="#14213D" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
        </symbol>
        <symbol id="ic-arrow" viewBox="0 0 14 14">
            <path d="M3 11L11 3M11 3H5M11 3V9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
        </symbol>
        <symbol id="ic-check" viewBox="0 0 14 14">
            <path d="M2 7.5L5.5 11L12 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/>
        </symbol>
    </defs>
</svg>
