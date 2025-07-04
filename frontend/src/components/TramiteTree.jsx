import React from 'react';
import TramiteNode from './TramiteNode';

const TramiteTree = ({ data, onSelectNode }) => {
    return (
        <div className="w-1/3 border-r p-4">
            <h2 className="text-lg font-bold mb-2">Trámites</h2>
            {data.map((node, i) => (
                <TramiteNode key={i} node={node} onSelect={onSelectNode} />
            ))}
        </div>
    );
};

export default TramiteTree;