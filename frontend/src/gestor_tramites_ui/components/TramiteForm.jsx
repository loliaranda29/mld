import React, { useState, useEffect } from 'react';

const TramiteForm = ({ selectedNode, onSave }) => {
    const [formData, setFormData] = useState({ title: '', description: '' });

    useEffect(() => {
        if (selectedNode) {
            setFormData({
                title: selectedNode.title || '',
                description: selectedNode.description || '',
            });
        }
    }, [selectedNode]);

    const handleChange = (e) => {
        setFormData({ ...formData, [e.target.name]: e.target.value });
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        onSave(formData);
    };

    return (
        <form onSubmit={handleSubmit} className="w-2/3 p-4">
            <h2 className="text-lg font-bold mb-4">Editar Trámite</h2>
            <input
                type="text"
                name="title"
                value={formData.title}
                onChange={handleChange}
                placeholder="Nombre del trámite"
                className="w-full mb-2 p-2 border"
            />
            <textarea
                name="description"
                value={formData.description}
                onChange={handleChange}
                placeholder="Descripción"
                className="w-full mb-4 p-2 border"
            />
            <button type="submit" className="bg-blue-500 text-white px-4 py-2 rounded">
                Guardar
            </button>
        </form>
    );
};

export default TramiteForm;