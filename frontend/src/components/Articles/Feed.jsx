import React, { useEffect, useState } from "react";
import { useDispatch, useSelector } from "react-redux";
import { fetchFeed, searchArticles } from "../../reducers/articlesSlice";
import SearchAndFilter from "./Search";
import { Article } from "./Article";

const Feed = () => {
    const dispatch = useDispatch();
    const { articles, loading, hasMore, page, isSearching, filters } = useSelector((state) => state.articles);
    const [ showFilter, setShowFilter ] = useState(false);

    useEffect(() => {
        dispatch(fetchFeed(1));
    }, [dispatch]);

    function loadMore(e){
        if( isSearching ){
            dispatch(searchArticles({ ...filters, page: page + 1}))
        }else {
            dispatch(fetchFeed(page + 1));
        }
    }

    return (
        <div className="p-4">
            <div className="flex justify-center items-center mb-6">
                <h1 className="text-2xl font-bold text-center">News Feed</h1>
                <button onClick={() => setShowFilter(!showFilter)} className="ms-auto">filter</button>
            </div>

            { showFilter && (
                <div className="mb-5">
                    <SearchAndFilter />
                </div>
            ) }

            {articles.length === 0 ? (
                <p className="text-center text-gray-600">No articles available. Please try again later.</p>
            ) : (
                <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    {articles.map((article) => (
                        <Article key={article.id} article={article} />
                    ))}
                </div>
            )}

            {hasMore && (
                <>
                {
                    loading 
                    ? <p className="mt-5">Loading...</p>
                    : <button onClick={loadMore} className="button text-center mt-4">
                            <p className="text-blue-500">Load more</p>
                        </button>
                }
                </>
                
            )}


        </div>
    );
};

export default Feed;

