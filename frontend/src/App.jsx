// src/App.jsx
import React from 'react';
import { Routes, Route } from 'react-router-dom';
import Home from './pages/Home';
import Login from './pages/Login';
import ListadoTramites from "./pages/admin/tramites/ListadoTramites";

export default function App() {
  return (
    <Routes>
      <Route path="/" element={<Home />} />
      <Route path="/login" element={<Login />} />
      <Route path="/admin/tramites" element={<ListadoTramites />} />
    </Routes>
  );
}
