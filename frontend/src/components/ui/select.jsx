import React from 'react';

export const Select = ({ options = [], className = '', ...props }) => {
  return (
    <select
      {...props}
      className={`border border-gray-300 rounded px-4 py-2 w-full focus:outline-none focus:ring focus:border-blue-400 ${className}`}
    >
      {options.map((opt, i) => (
        <option key={i} value={opt.value}>
          {opt.label}
        </option>
      ))}
    </select>
  );
};
