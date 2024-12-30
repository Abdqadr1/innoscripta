import { createSlice, createAsyncThunk } from "@reduxjs/toolkit";
import axios from "axios";
import { logout } from "./authSlice";

const API_URL = import.meta.env.VITE_API_URL;

export const fetchFeed = createAsyncThunk("articles/fetchFeed", async (page, thunkAPI) => {
  const { auth } = thunkAPI.getState();
  try {
    const { data } = await axios.get(`${API_URL}/news_feed?page=${page}&use_preference=${Number(auth.user.is_preference)}`, {
      headers: { Authorization: `Bearer ${auth.token}` },
    });
    return data;
  } catch (error) {
    if (error.response?.status === 401) {
      thunkAPI.dispatch(logout());
    }
    return thunkAPI.rejectWithValue(error.response.data.message);
  }
});


export const searchArticles = createAsyncThunk("articles/searchArticles", async (filters, thunkAPI) => {
  const { auth } = thunkAPI.getState();
  try {
    const params = new URLSearchParams(filters).toString();
    const { data } = await axios.get(`${API_URL}/articles/search?${params}`, {
        headers: { Authorization: `Bearer ${auth.token}` },
    });

    return data;
  } catch (error) {
    if (error.response?.status === 401) {
      thunkAPI.dispatch(logout());
    }
    return thunkAPI.rejectWithValue(error.response.data.message);
  }
});

const articlesSlice = createSlice({
  name: "articles",
  initialState: {
    articles: [],
    hasMore: true,
    loading: false,
    isSearching: false,
    error: null,
    page: 0,
    filters: {
        category: null,
        source: null,
        date: null,
    }
  },
  reducers: {
    setFilters: (state, action) => {
        state.filters = action.payload
    }
  },
  extraReducers: (builder) => {
    builder
      .addCase(fetchFeed.pending, (state) => {
        state.loading = true;
        state.error = null;
        state.isSearching = false;
      })
      .addCase(fetchFeed.fulfilled, (state, action) => {
        state.loading = false;
        state.page = action.payload.meta.current_page;
        state.articles = action.payload.meta.current_page == 1 ? action.payload.data : [ ...state.articles, ...action.payload.data ];
        state.hasMore = Boolean( action.payload.meta.current_page < action.payload.meta.last_page );
      })
      .addCase(fetchFeed.rejected, (state, action) => {
        state.loading = false;
        state.error = action.payload;
      })
      .addCase(searchArticles.pending, (state) => {
        state.loading = true;
        state.error = null;
        state.isSearching = true;
      })
      .addCase(searchArticles.fulfilled, (state, action) => {
        state.loading = false;
        state.page = action.payload.meta.current_page;
        state.articles = action.payload.meta.current_page == 1 ? action.payload.data : [ ...state.articles, ...action.payload.data ];
        state.hasMore = Boolean( action.payload.meta.current_page < action.payload.meta.last_page );
      })
      .addCase(searchArticles.rejected, (state, action) => {
        state.loading = false;
        state.error = action.payload;
      });
  },
});


export const { setFilters } = articlesSlice.actions;

export default articlesSlice.reducer;
