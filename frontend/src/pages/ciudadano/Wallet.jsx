import React from "react";
import Layout from "../../components/layout/Layout";

export default function MiWallet() {
  return (
    <Layout>
      <div className="p-6">
        <h2 className="text-2xl font-bold mb-4">Mi Wallet</h2>
        <p className="text-gray-600">Estado de cuenta y saldo actual:</p>
        <div className="mt-6 p-6 bg-white rounded-lg shadow">
          <span className="text-4xl font-semibold">$0.00</span>
          <p className="text-gray-500">Saldo disponible</p>
        </div>
        {/* TODO: integración real con blockchain / Quark ID */}
      </div>
    </Layout>
  );
}
