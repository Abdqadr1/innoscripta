import React, { useEffect, useState } from "react";
import { useDispatch, useSelector } from "react-redux";
import { fetchFeed, searchArticles } from "../../reducers/articlesSlice";
import SearchAndFilter from "./Search";
import { Article } from "./Article";
import NoArticles from "./NoArticles";
import Preference from "../Preference";
import { updatePreference } from "../../reducers/authSlice";

const Feed = () => {
    const dispatch = useDispatch();
    const { articles, loading, hasMore, page, isSearching, filters } = useSelector((state) => state.articles);
    const [showFilter, setShowFilter] = useState(false);

    const refreshFeed = () => {
        dispatch(fetchFeed(1));
    }

    useEffect(() => {
        refreshFeed();
    }, [dispatch]);



    function togglePreference(e) {
        dispatch(
            updatePreference(
                undefined,
                () => refreshFeed()
            )
        );
    }

    function loadMore(e) {
        if (isSearching) {
            dispatch(searchArticles({ ...filters, page: page + 1 }))
        } else {
            dispatch(fetchFeed(page + 1));
        }
    }

    return (
        <div className="p-4">
            <div className="flex justify-between items-center mb-6">
                <Preference togglePreference={togglePreference} />
                <h1 className="text-2xl font-bold text-center">News Feed</h1>
                <button title="Filter" onClick={() => setShowFilter(!showFilter)} className="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="none" stroke="#1227b8" strokeLinecap="round" strokeMiterlimit="10" strokeWidth="1.5" d="M21.25 12H8.895m-4.361 0H2.75m18.5 6.607h-5.748m-4.361 0H2.75m18.5-13.214h-3.105m-4.361 0H2.75m13.214 2.18a2.18 2.18 0 1 0 0-4.36a2.18 2.18 0 0 0 0 4.36Zm-9.25 6.607a2.18 2.18 0 1 0 0-4.36a2.18 2.18 0 0 0 0 4.36Zm6.607 6.608a2.18 2.18 0 1 0 0-4.361a2.18 2.18 0 0 0 0 4.36Z" />
                    </svg>
                </button>
            </div>

            {showFilter && (
                <div className="mb-5">
                    <SearchAndFilter />
                </div>
            )}
            {(articles.length == 0 && !loading) ? (
                <NoArticles refresh={refreshFeed} />
            ) : (
                <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                    {articles.map((article) => (
                        <Article key={article.id} article={article} />
                    ))}
                </div>
            )}



            <div className="flex justify-center">
                {
                    loading && (
                        <p className="mt-5">Loading articles...</p>
                    )
                }
                {
                    ( hasMore &&  !loading ) &&(
                        <button onClick={loadMore} className="button text-center mt-4">
                            <p className="text-blue-500">Load more</p>
                        </button>
                    )
                }
            </div>


        </div>
    );
};

export default Feed;

