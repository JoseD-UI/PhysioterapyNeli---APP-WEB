import React, { useState } from 'react';
import { Moon, Sun } from 'lucide-react';
import { motion, AnimatePresence } from 'framer-motion';
import { useTheme } from '../theme-provider';

export function ThemeToggle() {
    const { theme, setTheme } = useTheme();
    const [isHovered, setIsHovered] = useState(false);

    const toggleTheme = () => {
        setTheme(theme === 'light' ? 'dark' : 'light');
    };

    return (
        <div className="fixed bottom-6 right-6 z-[100]">
            <motion.button
                whileHover={{ scale: 1.05 }}
                whileTap={{ scale: 0.95 }}
                onClick={toggleTheme}
                onMouseEnter={() => setIsHovered(true)}
                onMouseLeave={() => setIsHovered(false)}
                className="flex items-center gap-2 p-3 rounded-full bg-white dark:bg-gray-800 shadow-xl border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 transition-colors"
                aria-label="Toggle Theme"
            >
                <motion.div
                    animate={{ rotate: theme === 'light' ? 0 : 180 }}
                    transition={{ duration: 0.5 }}
                >
                    {theme === 'light' ? 
                        <Moon className="w-5 h-5 text-blue-600 fill-blue-600/10" /> : 
                        <Sun className="w-5 h-5 text-yellow-400 fill-yellow-400/20" />
                    }
                </motion.div>

                <AnimatePresence>
                    {isHovered && (
                        <motion.span
                            initial={{ width: 0, opacity: 0 }}
                            animate={{ width: 'auto', opacity: 1 }}
                            exit={{ width: 0, opacity: 0 }}
                            className="overflow-hidden whitespace-nowrap text-sm font-medium pr-1"
                        >
                            {theme === 'light' ? 'Cambiar a Modo Oscuro' : 'Cambiar a Modo Claro'}
                        </motion.span>
                    )}
                </AnimatePresence>
            </motion.button>
        </div>
    );
}
