export default function TagBadge({ tag }) {
    return (
        <span
            className="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium border"
            style={{
                backgroundColor: tag.color ? `${tag.color}20` : '#f3f4f6',
                borderColor: tag.color || '#d1d5db',
                color: tag.color || '#374151',
            }}
        >
            {tag.name}
        </span>
    )
}
