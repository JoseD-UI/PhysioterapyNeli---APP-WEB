import React from 'react';
import { motion } from 'framer-motion';

export default function AuthLayout({ children, title, subtitle, cardClassName = "max-w-md" }) {
    return (
        <div className="min-h-screen w-full flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800 p-4 transition-colors duration-500 relative">
            {/* Background Decorations */}
            <div className="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-400/20 rounded-full blur-[100px] pointer-events-none" />
            <div className="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-purple-400/20 rounded-full blur-[100px] pointer-events-none" />

            <motion.div 
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.5, ease: "easeOut" }}
                className={`w-full ${cardClassName} relative z-10`}
            >
                {/* Glass Card */}
                <div className="backdrop-blur-xl bg-white/70 dark:bg-gray-800/60 border border-white/50 dark:border-gray-700 shadow-2xl rounded-2xl overflow-hidden ring-1 ring-black/5">
                    <div className="p-8">
                        {(title || subtitle) && (
                            <div className="text-center mb-8">
                                {title && (
                                    <h2 className="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-400 dark:to-indigo-400 mb-2">
                                        {title}
                                    </h2>
                                )}
                                {subtitle && (
                                    <p className="text-gray-500 dark:text-gray-400 font-medium">
                                        {subtitle}
                                    </p>
                                )}
                            </div>
                        )}
                        {children}
                    </div>
                </div>

                {/* Footer / Copyright */}
                <div className="text-center mt-6 text-xs text-gray-400 dark:text-gray-500 font-medium">
                    &copy; {new Date().getFullYear()} PhysioApp. Secure System.
                </div>
            </motion.div>
        </div>
    );
}
