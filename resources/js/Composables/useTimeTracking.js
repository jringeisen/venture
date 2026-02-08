import { ref, computed, onMounted, onUnmounted, toValue } from 'vue';

export function useTimeTracking(courseId, weekNumber, dayNumber) {
    // State
    const sessionId = ref(null);
    const trackingStartTime = ref(null);
    const elapsedSeconds = ref(0);
    const displaySeconds = ref(0);
    const isPageVisible = ref(true);
    const isUserActive = ref(true);
    const lastActivityTime = ref(Date.now());

    // Constants
    const INACTIVITY_TIMEOUT = 5 * 60 * 1000;
    const TRACKING_INTERVAL = 30 * 1000;
    const ACTIVITY_CHECK_INTERVAL = 1000;
    const DISPLAY_UPDATE_INTERVAL = 1000;

    // Internal timers
    let trackingTimer = null;
    let activityCheckTimer = null;
    let displayTimer = null;

    // Start a learning session
    const startLearningSession = async () => {
        try {
            const response = await window.axios.post(
                `/student/courses/${toValue(courseId)}/week/${toValue(weekNumber)}/day/${toValue(dayNumber)}/start-session`
            );
            if (response.data.success) {
                sessionId.value = response.data.session_id;
                trackingStartTime.value = Date.now();
                elapsedSeconds.value = 0;
                startTrackingTimers();
            }
        } catch (error) {
        }
    };

    // Send time update to server
    const sendTimeUpdate = async () => {
        if (!sessionId.value || !isPageVisible.value || !isUserActive.value) {
            return;
        }

        const now = Date.now();
        const secondsSinceLastUpdate = Math.floor((now - trackingStartTime.value) / 1000);

        if (secondsSinceLastUpdate > 0) {
            try {
                await window.axios.post(
                    `/student/courses/${toValue(courseId)}/week/${toValue(weekNumber)}/day/${toValue(dayNumber)}/track-time`,
                    {
                        seconds: secondsSinceLastUpdate,
                        session_id: sessionId.value,
                    }
                );
                elapsedSeconds.value += secondsSinceLastUpdate;
                trackingStartTime.value = now;
            } catch (error) {
            }
        }
    };

    // End the learning session
    const endLearningSession = async () => {
        if (!sessionId.value) {
            return;
        }

        const finalSeconds = Math.floor((Date.now() - trackingStartTime.value) / 1000);

        try {
            await window.axios.post(
                `/student/courses/${toValue(courseId)}/end-session`,
                {
                    session_id: sessionId.value,
                    final_seconds: finalSeconds > 0 ? finalSeconds : 0,
                }
            );
        } catch (error) {
        }

        sessionId.value = null;
    };

    // Start tracking timers
    const startTrackingTimers = () => {
        stopTrackingTimers();

        trackingTimer = setInterval(() => {
            if (isPageVisible.value && isUserActive.value) {
                sendTimeUpdate();
            }
        }, TRACKING_INTERVAL);

        activityCheckTimer = setInterval(() => {
            const timeSinceActivity = Date.now() - lastActivityTime.value;
            if (timeSinceActivity > INACTIVITY_TIMEOUT && isUserActive.value) {
                isUserActive.value = false;
                sendTimeUpdate();
            }
        }, ACTIVITY_CHECK_INTERVAL);

        displayTimer = setInterval(() => {
            if (isPageVisible.value && isUserActive.value && trackingStartTime.value) {
                displaySeconds.value = elapsedSeconds.value + Math.floor((Date.now() - trackingStartTime.value) / 1000);
            }
        }, DISPLAY_UPDATE_INTERVAL);
    };

    // Stop tracking timers
    const stopTrackingTimers = () => {
        if (trackingTimer) {
            clearInterval(trackingTimer);
            trackingTimer = null;
        }
        if (activityCheckTimer) {
            clearInterval(activityCheckTimer);
            activityCheckTimer = null;
        }
        if (displayTimer) {
            clearInterval(displayTimer);
            displayTimer = null;
        }
    };

    // Handle user activity
    const handleUserActivity = () => {
        lastActivityTime.value = Date.now();
        if (!isUserActive.value) {
            isUserActive.value = true;
            trackingStartTime.value = Date.now();
        }
    };

    // Handle page visibility change
    const handleVisibilityChange = () => {
        isPageVisible.value = !document.hidden;

        if (document.hidden) {
            sendTimeUpdate();
        } else {
            trackingStartTime.value = Date.now();
            lastActivityTime.value = Date.now();
        }
    };

    // Set up activity listeners
    const setupActivityListeners = () => {
        const events = ['mousemove', 'keypress', 'scroll', 'mousedown', 'touchstart', 'click'];
        events.forEach(event => {
            document.addEventListener(event, handleUserActivity, { passive: true });
        });

        document.addEventListener('visibilitychange', handleVisibilityChange);
    };

    // Remove activity listeners
    const removeActivityListeners = () => {
        const events = ['mousemove', 'keypress', 'scroll', 'mousedown', 'touchstart', 'click'];
        events.forEach(event => {
            document.removeEventListener(event, handleUserActivity);
        });

        document.removeEventListener('visibilitychange', handleVisibilityChange);
    };

    // Computed
    const formattedElapsedTime = computed(() => {
        const totalSeconds = displaySeconds.value;
        const minutes = Math.floor(totalSeconds / 60);
        const seconds = totalSeconds % 60;

        if (minutes >= 60) {
            const hours = Math.floor(minutes / 60);
            const remainingMinutes = minutes % 60;
            return `${hours}h ${remainingMinutes}m`;
        }

        return `${minutes}m ${seconds.toString().padStart(2, '0')}s`;
    });

    // Lifecycle hooks
    onMounted(() => {
        setupActivityListeners();
        startLearningSession();
    });

    onUnmounted(() => {
        stopTrackingTimers();
        removeActivityListeners();
        endLearningSession();
    });

    return {
        sessionId,
        displaySeconds,
        isPageVisible,
        isUserActive,
        formattedElapsedTime,
        startLearningSession,
        endLearningSession,
        sendTimeUpdate,
    };
}
