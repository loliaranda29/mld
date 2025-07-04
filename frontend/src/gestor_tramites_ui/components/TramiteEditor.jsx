import React, { useState } from 'react';
import TramiteTree from './TramiteTree';
import TramiteForm from './TramiteForm';

const TramiteEditor = () => {
    const [selectedNode, setSelectedNode] = useState(null);
    const [treeData, setTreeData] = useState([]);

    const handleNodeSelect = (node) => setSelectedNode(node);
    const handleSave = (formData) => {
        // Lógica para guardar/actualizar el nodo
    };

    return (
        <div className="flex h-full">
            <TramiteTree data={treeData} onSelectNode={handleNodeSelect} />
            <TramiteForm selectedNode={selectedNode} onSave={handleSave} />
        </div>
    );
};

export default TramiteEditor;