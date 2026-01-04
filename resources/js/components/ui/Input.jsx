import React, { forwardRef } from 'react';
import { cn } from '../../lib/utils';
import { motion } from 'framer-motion';

export const Input = forwardRef(({ 
    className, 
    label, 
    error, 
    type = 'text', 
    ...props 
}, ref) => {
    return (
        <div className="w-full space-y-1.5">
            {label && (
                <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">
                    {label}
                </label>
            )}
            <div className="relative group">
                <input
                    ref={ref}
                    type={type}
                    className={cn(
                        'flex h-11 w-full rounded-xl border border-gray-200 bg-gray-50/50 px-4 py-2 text-sm text-gray-900 placeholder:text-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 ease-out disabled:cursor-not-allowed disabled:opacity-50',
                        'dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:focus:ring-blue-400/20 dark:focus:border-blue-400',
                        error && 'border-red-500 focus:ring-red-500/20 focus:border-red-500 bg-red-50/10',
                        className
                    )}
                    {...props}
                />
            </div>
            {error && (
                <motion.p 
                    initial={{ opacity: 0, y: -5 }}
                    animate={{ opacity: 1, y: 0 }}
                    className="text-xs text-red-500 font-medium ml-1"
                >
                    {error}
                </motion.p>
            )}
        </div>
    );
});

Input.displayName = 'Input';
