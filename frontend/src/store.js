import { configureStore } from "@reduxjs/toolkit";
import authReducer from "./reducers/authSlice";
import preferencesReducer from "./reducers/preferencesSlice";
import articlesReducer from "./reducers/articlesSlice";

export const store = configureStore({
  reducer: {
    auth: authReducer,
    preferences: preferencesReducer,
    articles: articlesReducer,
  },
});
