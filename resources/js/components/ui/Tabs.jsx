import React, { useState, createContext, useContext } from 'react';
import { useTheme } from '../theme-provider';

const TabsContext = createContext();

const useTabs = () => {
    const context = useContext(TabsContext);
    if (!context) {
        throw new Error('useTabs must be used within Tabs');
    }
    return context;
};

export function Tabs({ value, onValueChange, children, className = '' }) {
    return (
        <TabsContext.Provider value={{ value, onValueChange }}>
            <div className={className}>{children}</div>
        </TabsContext.Provider>
    );
}

export function TabsList({ children, className = '' }) {
    const { theme } = useTheme();
    return (
        <div className={`flex border-b gap-1 ${
            theme === 'dark'
                ? 'border-gray-700 bg-gray-800'
                : 'border-gray-200 bg-white'
        } ${className}`}>
            {children}
        </div>
    );
}

export function TabsTrigger({ value, children, className = '' }) {
    const { value: selectedValue, onValueChange } = useTabs();
    const { theme } = useTheme();
    const isActive = selectedValue === value;

    return (
        <button
            onClick={() => onValueChange(value)}
            className={`px-4 py-3 font-medium transition-colors border-b-2 ${
                isActive
                    ? `border-blue-500 ${theme === 'dark' ? 'text-blue-400' : 'text-blue-600'}`
                    : `border-transparent ${
                        theme === 'dark'
                            ? 'text-gray-400 hover:text-gray-300'
                            : 'text-gray-600 hover:text-gray-900'
                    }`
            } ${className}`}
        >
            {children}
        </button>
    );
}

export function TabsContent({ value, children, className = '' }) {
    const { value: selectedValue } = useTabs();

    if (selectedValue !== value) return null;

    return <div className={className}>{children}</div>;
}
