import React from 'react';

const Card = ({ children, className = "" }) => {
  return (
    <div
      className={`bg-white rounded-lg shadow-md border p-4 hover:shadow-lg transition ${className}`}
    >
      {children}
    </div>
  );
};

export default Card;
