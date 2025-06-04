// src/App.jsx
import React from 'react';
import { Routes, Route } from 'react-router-dom';
import Home from './pages/Home';
import Login from './pages/Login';
import ListadoTramites from "./pages/admin/tramites/ListadoTramites";
import EditorTramite from "./pages/admin/tramites/EditorTramite";


export default function App() {
  return (
    <Routes>
      <Route path="/" element={<Home />} />
      <Route path="/login" element={<Login />} />
      <Route path="/admin/tramites" element={<ListadoTramites />} />
      <Route path="/admin/tramites/editor" element={<EditorTramite />} />
      <Route path="/admin/tramites" element={<ListadoTramites />} />
      <Route path="/admin/tramites/editor" element={<EditorTramite />} />
      <Route path="/admin/tramites/editar/:id" element={<EditorTramite />} />

    </Routes>
  );
}
