import React from 'react';
import { BrowserRouter, Routes, Route } from "react-router-dom";
import logo from './logo.svg';
import './App.css';

import Home from './Home';
import About from './About';
import Product from './Product';
import Login from './Login';
import Register from './Register';

function App() {
  return (
    <>
      <BrowserRouter>
        <Routes>
          <Route path="/" element={<Home />} />
          <Route path="/about" element={<About />} />
          <Route path="/login" element={<Login />} />
          <Route path="/register" element={<Register />} />
          <Route path="/product/:url" element={<Product url={window.location.pathname} />} />
        </Routes>
      </BrowserRouter>
    </>
  );
}

export default App;
