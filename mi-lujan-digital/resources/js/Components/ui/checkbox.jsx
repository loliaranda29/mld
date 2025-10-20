"use client";

import React from "react";

/**
 * Checkbox genérico, compatible con onCheckedChange (como Radix / shadcn).
 *
 * Props:
 * - checked: boolean | "indeterminate"
 * - onCheckedChange: (checked: boolean) => void
 * - required: boolean
 * - disabled: boolean
 * - className: string
 */
export default function Checkbox({
    checked = false,
    onCheckedChange,
    required = false,
    disabled = false,
    className = "",
}) {
    const handleChange = (e) => {
        const isChecked = e.target.checked;
        if (onCheckedChange) onCheckedChange(isChecked);
    };

    return (
        <input
            type="checkbox"
            checked={!!checked}
            onChange={handleChange}
            required={required}
            disabled={disabled}
            className={`w-4 h-4 rounded border-gray-300 text-sky-600 shadow-sm focus:ring-sky-500 focus:ring-2 transition-all disabled:opacity-60 disabled:cursor-not-allowed ${className}`}
        />
    );
}
