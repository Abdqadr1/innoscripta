import React from "react";

const NoArticles = ({ refresh }) => {
  return (
    <div className="flex justify-center items-center mt-20">

      <div className="flex flex-col items-center justify-center text-center p-6 border border-gray-300 rounded-lg shadow-md bg-white">
        <h2 className="text-xl font-bold text-gray-800">No Articles Available</h2>
        <p className="text-gray-600 mb-4">Please try again later or refresh the feed.</p>
        <button
          onClick={refresh}
          className="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-500"
        >
          Refresh Feed
        </button>
      </div>

    </div>
  );
};

export default NoArticles;
