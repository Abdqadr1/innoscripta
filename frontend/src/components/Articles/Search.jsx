import React, { useEffect, useState } from 'react';
import Select from 'react-select';
import { useDispatch, useSelector } from 'react-redux';
import { searchArticles, setFilters } from '../../reducers/articlesSlice';
import { fetchPreferences } from "../../reducers/preferencesSlice";

const SearchAndFilter = () => {
  const dispatch = useDispatch();
  const { loading } = useSelector((state) => state.articles);

  useEffect(() => {
    dispatch(fetchPreferences())
  }, [dispatch]);
  
  const [searchKeyword, setSearchKeyword] = useState('');
  const [selectedCategory, setSelectedCategory] = useState(null);
  const [selectedSource, setSelectedSource] = useState(null);
  const [selectedDate, setSelectedDate] = useState('');

  
  const { categories, sources } = useSelector((state) => ({
    categories: state.preferences.categories,
    sources: state.preferences.sources,
  }));

  const handleSearch = () => {
    const filters = {
      keyword: searchKeyword,
      category: selectedCategory?.value,
      source: selectedSource?.value,
      date: selectedDate,
      page: 1
    };
    dispatch( setFilters(filters) );
    dispatch(searchArticles(filters));
  };

  return (
    <div className="p-4 bg-white shadow-lg rounded-md">
    
      <div className="mb-4">
        <label htmlFor="search" className="block text-gray-700 font-medium mb-2">
          Search by Keyword
        </label>
        <input
          id="search"
          type="text"
          value={searchKeyword}
          onChange={(e) => setSearchKeyword(e.target.value)}
          placeholder="Enter a keyword..."
          className="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
      </div>

      <div className='flex flex-wrap items-center gap-2'>
      
        <div className="grow mb-4">
          <label className="block text-gray-700 font-medium mb-2">Filter by Category</label>
          <Select
            options={categories}
            value={selectedCategory}
            onChange={(selected) => setSelectedCategory(selected)}
            placeholder="Select a category..."
            classNamePrefix="react-select"
          />
        </div>

        <div className="grow mb-4">
          <label className="block text-gray-700 font-medium mb-2">Filter by Source</label>
          <Select
            options={sources}
            value={selectedSource}
            onChange={(selected) => setSelectedSource(selected)}
            placeholder="Select a source..."
            classNamePrefix="react-select"
          />
        </div>

        <div className="mb-4">
          <label htmlFor="date" className="block text-gray-700 font-medium mb-2">
            Filter by Date
          </label>
          <input
            id="date"
            type="date"
            value={selectedDate}
            onChange={(e) => setSelectedDate(e.target.value)}
            className="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>

      </div>


      <button
        onClick={handleSearch}
        className="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition"
      >
        { loading ? 'Searching...' : 'Search' }
      </button>
    </div>
  );
};

export default SearchAndFilter;
