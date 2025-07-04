import React from 'react';

const TramiteNode = ({ node, onSelect }) => {
    return (
        <div className="mb-2 cursor-pointer" onClick={() => onSelect(node)}>
            <span className="text-blue-600">{node.title}</span>
            {node.children && node.children.length > 0 && (
                <div className="ml-4">
                    {node.children.map((child, i) => (
                        <TramiteNode key={i} node={child} onSelect={onSelect} />
                    ))}
                </div>
            )}
        </div>
    );
};

export default TramiteNode;