export function Article({ article }) {
    return (
        <div
            key={article.id}
            className="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300"
        >
            <img
                src={article.image_url || "https://via.placeholder.com/300"}
                alt={article.title}
                className="w-full h-48 object-cover"
            />
            <div className="p-4">
                <h2 className="text-lg font-semibold mb-2">{article.title}</h2>
                
                <p className="text-sm text-gray-500 mb-2">
                    By {article.author.name || "Unknown Author"}
                </p>
                <p className="text-sm text-gray-700 mb-4">
                    {article.description}
                </p>
                <a
                    href={article.url}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="inline-block bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition-colors duration-200"
                >
                    Read More
                </a>
            </div>
        </div>
    );
}
