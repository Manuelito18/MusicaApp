import React, { useState, useEffect } from "react";
import { Plus, Music } from "lucide-react";
import ProductList from "./components/ProductList";
import ProductForm from "./components/ProductForm";
import {
  getProducts,
  createProduct,
  updateProduct,
  deleteProduct,
} from "./services/api";

function App() {
  const [products, setProducts] = useState([]);
  const [isFormOpen, setIsFormOpen] = useState(false);
  const [editingProduct, setEditingProduct] = useState(null);
  const [loading, setLoading] = useState(true);

  const loadProducts = async () => {
    try {
      const data = await getProducts();
      setProducts(data);
    } catch (error) {
      console.error("Error loading products:", error);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadProducts();
  }, []);

  const handleCreate = () => {
    setEditingProduct(null);
    setIsFormOpen(true);
  };

  const handleEdit = (product) => {
    setEditingProduct(product);
    setIsFormOpen(true);
  };

  const handleDelete = async (id) => {
    if (window.confirm("¿Estás seguro de eliminar este producto?")) {
      try {
        await deleteProduct(id);
        loadProducts();
      } catch (error) {
        console.error("Error deleting product:", error);
      }
    }
  };

  const handleSave = async (formData) => {
    try {
      if (editingProduct) {
        await updateProduct(editingProduct.IdProducto, formData);
      } else {
        await createProduct(formData);
      }
      setIsFormOpen(false);
      loadProducts();
    } catch (error) {
      console.error("Error saving product:", error);
      alert("Error al guardar el producto");
    }
  };

  return (
    <div className="min-h-screen pb-20">
      {/* Header */}
      <header className="mb-12 pt-8">
        <div className="flex items-center justify-between mb-2">
          <div className="flex items-center gap-3">
            <div className="p-3 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-lg shadow-indigo-500/30">
              <Music className="text-white" size={32} />
            </div>
            <div>
              <h1 className="text-4xl font-bold bg-gradient-to-r from-white to-slate-400 bg-clip-text text-transparent">
                MusicShop
              </h1>
              <p className="text-slate-400">Gestión de Inventario Premium</p>
            </div>
          </div>

          <button onClick={handleCreate} className="btn btn-primary">
            <Plus size={20} />
            Nuevo Producto
          </button>
        </div>
      </header>

      {/* Main Content */}
      <main>
        {loading ? (
          <div className="flex justify-center py-20">
            <div className="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500"></div>
          </div>
        ) : (
          <ProductList
            products={products}
            onEdit={handleEdit}
            onDelete={handleDelete}
          />
        )}
      </main>

      {/* Modal Form */}
      {isFormOpen && (
        <ProductForm
          product={editingProduct}
          onClose={() => setIsFormOpen(false)}
          onSave={handleSave}
        />
      )}
    </div>
  );
}

export default App;
