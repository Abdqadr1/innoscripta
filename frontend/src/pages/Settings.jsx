import React, { useEffect, useState } from "react";
import { useDispatch, useSelector } from "react-redux";
import { updatePreferences, fetchPreferences } from "../reducers/preferencesSlice";
import Select from "react-select";

const Settings = () => {
  const dispatch = useDispatch();
  const { preferences, loading, sources, categories, authors, error } = useSelector((state) => state.preferences);
  const [selectedPreferences, setSelectedPreferences] = useState({
    sources: [],
    categories: [],
    authors: [],
  });

  
  useEffect(() => {
    dispatch(fetchPreferences())
  }, [dispatch]);

  
  useEffect(() => {

    setSelectedPreferences( s => {
      return {
        sources: preferences.sources.map((source) => ({
            value: source.id,
            label: sources.find((s) => s.value == source.id)?.label || "",
          })),
          categories: preferences.categories.map((category) => ({
            value: category.id,
            label: categories.find((c) => c.value == category.id)?.label || "",
          })),
          authors: preferences.authors.map((author) => ({
            value: author.id,
            label: authors.find((a) => a.value == author.id)?.label || "",
          })),
      }

    });

  }, [preferences]);

  const handleChange = (type, selectedOptions) => {
    setSelectedPreferences((prev) => ({
      ...prev,
      [type]: selectedOptions || [],
    }));
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    dispatch(updatePreferences(selectedPreferences));
  };

  return (
    <div className="min-h-screen bg-gray-100 p-4">
      <div className="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6">
        <h1 className="text-2xl font-bold text-gray-800 mb-4">Settings</h1>

        {error && <p className="text-red-500">{error}</p>}
        <div className="mb-6">
          <h2 className="text-left text-lg font-semibold text-gray-700 mb-2">Sources</h2>
          <Select
            isMulti
            options={sources}
            value={selectedPreferences.sources}
            onChange={(selected) => handleChange("sources", selected)}
            placeholder="Select preferred sources..."
            className="react-select-container"
            classNamePrefix="react-select"
          />
        </div>

        <div className="mb-6">
          <h2 className="text-left text-lg font-semibold text-gray-700 mb-2">Categories</h2>
          <Select
            isMulti
            options={categories}
            value={selectedPreferences.categories}
            onChange={(selected) => handleChange("categories", selected)}
            placeholder="Select preferred categories..."
            className="react-select-container"
            classNamePrefix="react-select"
          />
        </div>

        <div className="mb-6">
          <h2 className="text-left text-lg font-semibold text-gray-700 mb-2">Authors</h2>
          <Select
            isMulti
            options={authors}
            value={selectedPreferences.authors}
            onChange={(selected) => handleChange("authors", selected)}
            placeholder="Select preferred authors..."
            className="react-select-container"
            classNamePrefix="react-select"
          />
        </div>


        <button
          onClick={handleSubmit}
          disabled={loading}
          className={`w-full py-2 px-4 text-white font-bold rounded-md ${
            loading
              ? "bg-blue-300 cursor-not-allowed"
              : "bg-blue-600 hover:bg-blue-700"
          }`}
        >
          {loading ? "Saving..." : "Save Preferences"}
        </button>
      </div>
    </div>
  );


};

export default Settings;
