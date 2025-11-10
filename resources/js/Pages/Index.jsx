import React, { useEffect, useState } from 'react';
import { Link, useForm } from '@inertiajs/react';

export default function Home({ auth }) {
    const { post } = useForm();
    const [towns, setTowns] = useState([]);
    const [counties, setCounties] = useState([]);

    useEffect(() => {
        fetch('/api/towns')
            .then(res => res.json())
            .then(json => setTowns(json.towns || []))
            .catch(err => console.error(err));

        fetch('/api/counties')
            .then(res => res.json())
            .then(json => setCounties(json.counties || []))
            .catch(err => console.error(err));
    }, []);

    const handleLogout = () => {
        post('/logout'); // calls AuthenticatedSessionController@destroy
    };

    return (
        <div className="p-6">
            <h1 className="text-2xl font-bold mb-4">Homepage</h1>

            {auth?.user ? (
                <button
                    onClick={handleLogout}
                    className="bg-red-500 text-white px-4 py-2 rounded"
                >
                    Logout ({auth.user.email})
                </button>
            ) : (
                <Link
                    href="/login"
                    className="bg-blue-500 text-white px-4 py-2 rounded"
                >
                    Login
                </Link>
            )}
 {/* Counties Table */}
 <h2 className="text-xl font-semibold mt-8 mb-2">Counties</h2>
            <table className="border-collapse border border-gray-300 w-full">
                <thead>
                    <tr>
                        <th className="border px-4 py-2">ID</th>
                        <th className="border px-4 py-2">Name</th>
                    </tr>
                </thead>
                <tbody>
                    {counties.map((county) => (
                        <tr key={county.id}>
                            <td className="border px-4 py-2">{county.id}</td>
                            <td className="border px-4 py-2">{county.name}</td>
                        </tr>
                    ))}
                </tbody>
            </table>
            {/* Towns Table */}
            <h2 className="text-xl font-semibold mt-8 mb-2">Towns</h2>
            <table className="border-collapse border border-gray-300 w-full">
                <thead>
                    <tr>
                        <th className="border px-4 py-2">ID</th>
                        <th className="border px-4 py-2">Name</th>
                    </tr>
                </thead>
                <tbody>
                    {towns.map((town) => (
                        <tr key={town.id}>
                            <td className="border px-4 py-2">{town.id}</td>
                            <td className="border px-4 py-2">{town.name}</td>
                        </tr>
                    ))}
                </tbody>
            </table>

           
        </div>
    );
}
