import { createSlice, createAsyncThunk } from "@reduxjs/toolkit";
import axios from "axios";

const API_URL = import.meta.env.VITE_API_URL;

export const fetchPreferences = createAsyncThunk("preferences/fetchPreferences", async (_, thunkAPI) => {
  const { auth } = thunkAPI.getState();
  try {
    const { data } = await axios.get(`${API_URL}/user/preferences`, {
      headers: { Authorization: `Bearer ${auth.token}` },
    });
    return data;
  } catch (error) {
    return thunkAPI.rejectWithValue(error.response.data.message);
  }
});

export const updatePreferences = createAsyncThunk(
  "preferences/updatePreferences",
  async (preferences, thunkAPI) => {
    const { auth } = thunkAPI.getState();
    try {
        preferences.authors = preferences.authors.map( auth => auth.value );
        preferences.categories = preferences.categories.map( auth => auth.value );
        preferences.sources = preferences.sources.map( auth => auth.value );

      const { data } = await axios.post(`${API_URL}/user/preferences`, preferences, {
        headers: { Authorization: `Bearer ${auth.token}` },
      });
      return data;
    } catch (error) {
      return thunkAPI.rejectWithValue(error.response.data.message);
    }
  }
);

const preferencesSlice = createSlice({
  name: "preferences",
  initialState: {
    preferences: {
      sources: [],
      categories: [],
      authors: [],
    },
    sources: [],
    categories: [],
    authors: [],
    loading: false,
    error: null,
  },
  reducers: {},
  extraReducers: (builder) => {
    builder
      .addCase(fetchPreferences.pending, (state) => {
        state.loading = true;
        state.error = null;
      })
      .addCase(fetchPreferences.fulfilled, (state, action) => {
        state.loading = false;
        state.preferences = action.payload.preferences;
        state.authors = action.payload.authors.map((author) => ({
                                                            value: author.id,
                                                            label: author.name,
                                                        }));
                                                        
        state.categories = action.payload.categories.map((author) => ({
            value: author.id,
            label: author.name,
        }));
        
        state.sources = action.payload.sources.map((author) => ({
            value: author.id,
            label: author.name,
        }));

      })
      .addCase(fetchPreferences.rejected, (state, action) => {
        state.loading = false;
        state.error = action.payload;
      })
      .addCase(updatePreferences.pending, (state) => {
        state.loading = true;
        state.error = null;
      })
      .addCase(updatePreferences.fulfilled, (state, action) => {
        state.loading = false;
        // state.preferences = action.payload;
      })
      .addCase(updatePreferences.rejected, (state, action) => {
        state.loading = false;
        state.error = action.payload;
      });
  },
});

export default preferencesSlice.reducer;
