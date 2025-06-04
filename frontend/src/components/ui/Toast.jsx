import React from "react";

export default function Toast({ type = "success", message }) {
  const color =
    type === "success"
      ? "bg-green-500"
      : type === "error"
      ? "bg-red-500"
      : "bg-gray-700";

  return (
    <div
      className={`fixed bottom-4 right-4 z-50 px-4 py-2 rounded text-white shadow-md ${color}`}
    >
      {message}
    </div>
  );
}
