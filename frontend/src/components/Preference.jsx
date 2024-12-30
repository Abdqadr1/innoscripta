import React from "react";
import { useSelector } from "react-redux";

export default function Preference({ togglePreference }) {
    const { user, loading } = useSelector((state) => state.auth);


  return (
    <div className="p-2 bg-gray-100 rounded-lg shadow-md">

        <div className="flex items-center justify-between gap-2">
          <label className="text-gray-700">
            {'Use Preferences'}
          </label>
          <input
            disabled={loading}
            type="checkbox"
            checked={user.is_preference}
            onChange={togglePreference}
            className="toggle-checkbox"
          />
        </div>

    </div>
  );
};
