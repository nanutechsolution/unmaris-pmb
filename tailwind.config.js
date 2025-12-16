import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';


/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            // 🎨 PALET WARNA KHUSUS UNMARIS
            colors: {
                unmaris: {
                    // Kuning Emas (Dominan di Logo) - Melambangkan Energi & Optimisme
                    yellow: '#FACC15', // Base
                    'yellow-light': '#FEF08A', // Untuk background muda
                    'yellow-dark': '#EAB308', // Untuk hover state
                    
                    // Biru Navy (Dominan di Text/Border) - Melambangkan Kedalaman Ilmu
                    blue: '#1E3A8A', // Base (Blue-900 tailwind)
                    'blue-light': '#3B82F6', // Aksen terang
                    
                    // Hijau (Aksen Padi)
                    green: '#16A34A',
                    
                    // Putih & Hitam Mutlak untuk Kontras Tinggi
                    white: '#FFFFFF',
                    black: '#111827',
                }
            },

            // 🌑 SHADOW NEO-BRUTALIST (GEN Z STYLE)
            // Menggunakan warna biru navy UNMARIS sebagai bayangan solid
            boxShadow: {
                'neo': '4px 4px 0px 0px #1E3A8A',        // Bayangan default
                'neo-sm': '2px 2px 0px 0px #1E3A8A',     // Bayangan kecil (tombol kecil/badge)
                'neo-lg': '8px 8px 0px 0px #1E3A8A',     // Bayangan besar (container utama)
                'neo-yellow': '4px 4px 0px 0px #FACC15', // Bayangan variasi kuning
                'neo-hover': '0px 0px 0px 0px #1E3A8A',  // Efek saat diklik (bayangan hilang)
            },

            // 🔠 TYPOGRAPHY
            fontFamily: {
                // Menambahkan font yang lebih modern/geometris jika ada
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                // Opsi font display yang "Bold" untuk judul (Misal: Syne, Space Grotesk)
                display: ['Figtree', 'sans-serif'], 
            },

            // ✨ ANIMASI
            animation: {
                'fade-in-down': 'fadeInDown 0.5s ease-out',
                'fade-in-up': 'fadeInUp 0.5s ease-out',
                'bounce-slight': 'bounceSlight 2s infinite',
            },
            keyframes: {
                fadeInDown: {
                    '0%': { opacity: '0', transform: 'translateY(-20px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                fadeInUp: {
                    '0%': { opacity: '0', transform: 'translateY(20px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                bounceSlight: {
                    '0%, 100%': { transform: 'translateY(-3%)' },
                    '50%': { transform: 'translateY(0)' },
                }
            },
        },
    },

    plugins: [require('@tailwindcss/forms')],
};