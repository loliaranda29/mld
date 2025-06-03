import React from "react";
import { Link } from "react-router-dom";
import Layout from "../components/layout/Layout";
import banner from '../assets/logo_mld.jpg';


export default function Home() {
  return (
    <Layout>
      {/* Hero con Banner e ingreso superior */}
      <section className="relative">
        <img
          src={banner}
          alt="Banner Mi Luján Digital"
          className="w-full h-[400px] object-cover"
        />

        <div className="absolute top-0 right-0 m-6 flex gap-4">
          <Link to="/login" className="px-4 py-2 bg-white text-[#248B89FF] border border-[#248B89FF] rounded-md font-semibold hover:bg-[#e7f1f0]">Iniciar Sesión</Link>
          <Link to="/registro" className="px-4 py-2 bg-[#248B89FF] text-white rounded-md font-semibold hover:bg-[#1f706e]">Registrarse</Link>
          <Link to="/guia-tramites" className="px-4 py-2 bg-white text-[#248B89FF] border border-[#248B89FF] rounded-md font-semibold hover:bg-[#e7f1f0]">Guía de Trámites</Link>
        </div>
      </section>

      {/* Buscador de Trámites */}
      <section className="py-12 px-6 bg-white text-center">
        <h2 className="text-2xl font-bold text-[#1F2937] mb-6">Buscá tu trámite</h2>
        <div className="max-w-xl mx-auto">
          <input
            type="text"
            placeholder="Ingresá palabras clave o el nombre del trámite"
            className="w-full border border-gray-300 rounded-xl px-4 py-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#248B89FF]"
          />
        </div>
      </section>

      {/* Trámites y servicios más buscados */}
      <section className="py-16 px-6 bg-[#F8FAFC]">
        <h2 className="text-2xl font-bold text-[#1F2937] text-center mb-10">Trámites y Servicios más buscados</h2>
        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
          <Link to="/licencia-conducir" className="bg-white p-5 rounded-xl shadow hover:shadow-md transition">
            <h3 className="text-lg font-semibold text-[#1F2937]">Licencia de Conducir</h3>
            <p className="text-sm text-[#4B5563]">Renová o sacá tu licencia fácilmente.</p>
          </Link>
          <Link to="/habilitacion-comercial" className="bg-white p-5 rounded-xl shadow hover:shadow-md transition">
            <h3 className="text-lg font-semibold text-[#1F2937]">Habilitación Comercial</h3>
            <p className="text-sm text-[#4B5563]">Gestioná permisos para tu comercio.</p>
          </Link>
          <Link to="/permiso-construccion" className="bg-white p-5 rounded-xl shadow hover:shadow-md transition">
            <h3 className="text-lg font-semibold text-[#1F2937]">Permiso de Construcción</h3>
            <p className="text-sm text-[#4B5563]">Solicitá tu permiso online.</p>
          </Link>
        </div>
      </section>

      {/* Features */}
      <section className="py-16 px-6 grid md:grid-cols-3 gap-6 bg-[#F8FAFC]">
        <div className="bg-white rounded-xl shadow-md p-6">
          <h2 className="text-lg font-semibold text-[#111827] mb-1">Trámites Online</h2>
          <p className="text-sm text-[#4B5563]">Realizá tus trámites desde casa, sin filas ni demoras.</p>
        </div>
        <div className="bg-white rounded-xl shadow-md p-6">
          <h2 className="text-lg font-semibold text-[#111827] mb-1">Seguimiento en Tiempo Real</h2>
          <p className="text-sm text-[#4B5563]">Consultá el estado de tus gestiones en cualquier momento.</p>
        </div>
        <div className="bg-white rounded-xl shadow-md p-6">
          <h2 className="text-lg font-semibold text-[#111827] mb-1">Identidad Digital</h2>
          <p className="text-sm text-[#4B5563]">Accedé de forma segura con tu identidad verificada.</p>
        </div>
      </section>
    </Layout>
  );
}
