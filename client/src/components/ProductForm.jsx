import React, { useState, useEffect } from "react";
import { X, Save } from "lucide-react";

const ProductForm = ({ product, onClose, onSave }) => {
  const [formData, setFormData] = useState({
    nombre: "",
    descripcion: "",
    precio: "",
    stock: "",
    id_categoria: 1,
    id_marca: 1,
    id_estado: 1,
    imagen_url: "",
  });

  useEffect(() => {
    if (product) {
      setFormData({
        nombre: product.Nombre,
        descripcion: product.Descripcion,
        precio: product.Precio,
        stock: product.Stock,
        id_categoria: product.IdCategoria || 1,
        id_marca: product.IdMarca || 1,
        id_estado: product.IdEstadoProducto || 1,
        imagen_url: product.ImagenURL || "",
      });
    }
  }, [product]);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prev) => ({ ...prev, [name]: value }));
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    onSave(formData);
  };

  return (
    <div className="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50 animate-fade-in">
      <div className="glass-panel w-full max-w-2xl p-6 relative">
        <button
          onClick={onClose}
          className="absolute top-4 right-4 text-gray-400 hover:text-white"
        >
          <X size={24} />
        </button>

        <h2 className="text-2xl font-bold mb-6 bg-gradient-to-r from-indigo-400 to-cyan-400 bg-clip-text text-transparent">
          {product ? "Editar Producto" : "Nuevo Producto"}
        </h2>

        <form
          onSubmit={handleSubmit}
          className="grid grid-cols-1 md:grid-cols-2 gap-6"
        >
          <div className="form-group col-span-2">
            <label className="form-label">Nombre del Producto</label>
            <input
              type="text"
              name="nombre"
              value={formData.nombre}
              onChange={handleChange}
              className="form-input"
              required
            />
          </div>

          <div className="form-group col-span-2">
            <label className="form-label">Descripción</label>
            <textarea
              name="descripcion"
              value={formData.descripcion}
              onChange={handleChange}
              className="form-input h-24 resize-none"
            />
          </div>

          <div className="form-group">
            <label className="form-label">Precio</label>
            <input
              type="number"
              step="0.01"
              name="precio"
              value={formData.precio}
              onChange={handleChange}
              className="form-input"
              required
            />
          </div>

          <div className="form-group">
            <label className="form-label">Stock</label>
            <input
              type="number"
              name="stock"
              value={formData.stock}
              onChange={handleChange}
              className="form-input"
              required
            />
          </div>

          <div className="form-group">
            <label className="form-label">Categoría (ID)</label>
            <select
              name="id_categoria"
              value={formData.id_categoria}
              onChange={handleChange}
              className="form-input"
            >
              <option value="1">Cuerdas</option>
              <option value="2">Percusión</option>
              <option value="3">Viento</option>
            </select>
          </div>

          <div className="form-group">
            <label className="form-label">Marca (ID)</label>
            <select
              name="id_marca"
              value={formData.id_marca}
              onChange={handleChange}
              className="form-input"
            >
              <option value="1">Fender</option>
              <option value="2">Yamaha</option>
              <option value="3">Gibson</option>
            </select>
          </div>

          <div className="form-group col-span-2">
            <label className="form-label">URL Imagen</label>
            <input
              type="text"
              name="imagen_url"
              value={formData.imagen_url}
              onChange={handleChange}
              className="form-input"
              placeholder="https://..."
            />
          </div>

          <div className="col-span-2 flex justify-end gap-4 mt-4">
            <button
              type="button"
              onClick={onClose}
              className="btn text-gray-300 hover:text-white"
            >
              Cancelar
            </button>
            <button type="submit" className="btn btn-primary">
              <Save size={18} />
              Guardar Producto
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default ProductForm;
