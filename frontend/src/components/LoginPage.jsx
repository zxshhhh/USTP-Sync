import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import api from '../api/axios';

export default function Login() {
  const navigate = useNavigate();
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');

    const handleLogin = async (e) => {
    e.preventDefault();

    try {
        // 1. Get CSRF cookie
        await api.get('/sanctum/csrf-cookie');

        // 2. Post login
        const response = await api.post('/api/login', {
        email,
        password,
        });

        localStorage.setItem('token', response.data.access_token);

        alert('Login successful!');
        navigate('/Mainpage');
    } catch (error) {
        console.error('Login failed:', error.response);
        alert('Login failed!');
    }
    };

  return (
    <form onSubmit={handleLogin}>
      <input
        type="email"
        placeholder="Email"
        value={email}
        onChange={(e) => setEmail(e.target.value)}
      /><br />
      <input
        type="password"
        placeholder="Password"
        value={password}
        onChange={(e) => setPassword(e.target.value)}
      /><br />
      <button type="submit">Login</button>
    </form>
  );
}
