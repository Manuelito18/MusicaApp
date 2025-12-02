import React from "react";
import { Edit2, Trash2, Package } from "lucide-react";

const ProductList = ({ products, onEdit, onDelete }) => {
  if (!products.length) {
    return (
      <div className="glass-panel p-12 text-center text-gray-400">
        <Package size={48} className="mx-auto mb-4 opacity-50" />
        <p className="text-xl">No hay productos disponibles</p>
      </div>
    );
  }

  return (
    <div className="glass-panel overflow-hidden animate-fade-in">
      <div className="table-container">
        <table>
          <thead>
            <tr>
              <th>Producto</th>
              <th>Categoría</th>
              <th>Marca</th>
              <th>Precio</th>
              <th>Stock</th>
              <th>Estado</th>
              <th className="text-right">Acciones</th>
            </tr>
          </thead>
          <tbody>
            {products.map((product) => (
              <tr key={product.IdProducto}>
                <td>
                  <div className="flex items-center gap-3">
                    <div className="w-10 h-10 rounded bg-slate-700 flex items-center justify-center overflow-hidden">
                      {product.ImagenURL ? (
                        <img
                          src={product.ImagenURL}
                          alt={product.Nombre}
                          className="w-full h-full object-cover"
                        />
                      ) : (
                        <Package size={20} className="text-slate-500" />
                      )}
                    </div>
                    <div>
                      <div className="font-medium text-white">
                        {product.Nombre}
                      </div>
                      <div className="text-xs text-gray-400 truncate max-w-[200px]">
                        {product.Descripcion}
                      </div>
                    </div>
                  </div>
                </td>
                <td>{product.Categoria || "N/A"}</td>
                <td>{product.Marca || "N/A"}</td>
                <td className="font-mono text-emerald-400">
                  S/ {parseFloat(product.Precio).toFixed(2)}
                </td>
                <td>
                  <span
                    className={`px-2 py-1 rounded-full text-xs ${
                      product.Stock > 5
                        ? "bg-emerald-500/20 text-emerald-400"
                        : "bg-red-500/20 text-red-400"
                    }`}
                  >
                    {product.Stock} un.
                  </span>
                </td>
                <td>
                  <span className="text-sm text-gray-300">
                    {product.Estado}
                  </span>
                </td>
                <td className="text-right">
                  <div className="flex justify-end gap-2">
                    <button
                      onClick={() => onEdit(product)}
                      className="p-2 hover:bg-white/10 rounded-lg text-blue-400 transition-colors"
                      title="Editar"
                    >
                      <Edit2 size={18} />
                    </button>
                    <button
                      onClick={() => onDelete(product.IdProducto)}
                      className="p-2 hover:bg-white/10 rounded-lg text-red-400 transition-colors"
                      title="Eliminar"
                    >
                      <Trash2 size={18} />
                    </button>
                  </div>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
};

export default ProductList;
