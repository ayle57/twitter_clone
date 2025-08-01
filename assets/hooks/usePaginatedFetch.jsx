import {useCallback, useState} from 'react';

export function usePaginatedFetch(url) {
    const [loading, setLoading] = useState(true);
    const [items, setItems] = useState([]);
    const load = useCallback(async () => {
        setLoading(true);
        const response = await fetch(url, {
            headers: {
                'Accept': 'application/ld+json',
                'Content-Type': 'application/ld+json',
            }
        });
        const responseData = await response.json();
        if (response.ok) {
            setItems(responseData["hydra:member"]);
        } else {
            console.error(responseData);
        }
        setLoading(false);
    }, [url]);
    return {
        items,
        load,
        loading
    }
}
