import React from 'react';
import { cn } from '../../lib/utils';

export function Skeleton({ className, ...props }) {
    return (
        <div
            className={cn(
                "animate-pulse rounded-md bg-gray-200 dark:bg-gray-700",
                className
            )}
            {...props}
        />
    );
}

export function SkeletonCard() {
    return (
        <div className="space-y-3">
             <Skeleton className="h-[125px] w-full rounded-xl" />
             <div className="space-y-2">
                 <Skeleton className="h-4 w-[250px]" />
                 <Skeleton className="h-4 w-[200px]" />
             </div>
        </div>
    );
}
