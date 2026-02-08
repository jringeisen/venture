export function useCourseFormatting() {
    const formatSubject = (subject) => {
        if (!subject) {
            return 'General';
        }
        return subject.charAt(0).toUpperCase() + subject.slice(1).toLowerCase();
    };

    const formatDifficulty = (difficulty) => {
        if (!difficulty) {
            return 'Beginner';
        }
        return difficulty.charAt(0).toUpperCase() + difficulty.slice(1).toLowerCase();
    };

    const difficultyColorClasses = (difficulty) => {
        const colors = {
            beginner: 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200',
            intermediate: 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200',
            advanced: 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200',
        };
        return colors[difficulty?.toLowerCase()] || colors.beginner;
    };

    return {
        formatSubject,
        formatDifficulty,
        difficultyColorClasses,
    };
}
