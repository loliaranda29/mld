import React, { createContext, useContext, useState } from "react";
import Toast from "../components/ui/Toast";

const ToastContext = createContext();

export function ToastProvider({ children }) {
  const [toast, setToast] = useState(null);

  const showToast = (type, message) => {
    setToast({ type, message });
    setTimeout(() => setToast(null), 3000);
  };

  return (
    <ToastContext.Provider value={{ showToast }}>
      {children}
      {toast && <Toast type={toast.type} message={toast.message} />}
    </ToastContext.Provider>
  );
}

export function useToast() {
  return useContext(ToastContext);
}
