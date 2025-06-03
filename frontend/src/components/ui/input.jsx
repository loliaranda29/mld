import React from 'react';

export const Input = ({ className = '', ...props }) => {
  return (
    <input
      {...props}
      className={`border border-gray-300 rounded px-4 py-2 w-full focus:outline-none focus:ring focus:border-blue-400 ${className}`}
    />
  );
};
