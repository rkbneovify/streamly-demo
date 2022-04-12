//
//  Drm+Key+Model.swift
//  Streamly
//
//  Created by Rohit Singh on 2/15/21.
//  Copyright © 2021 Rohit Singh. All rights reserved.
//

import Foundation

struct DrmKey : Codable {

    let data : String?
    let success:Bool?

    enum CodingKeys: String, CodingKey {

        case data = "data"
        case success = "success"

    }

    init(from decoder: Decoder) throws {
        let values = try decoder.container(keyedBy: CodingKeys.self)
        data = try values.decodeIfPresent(String.self, forKey: .data)
        success = try values.decodeIfPresent(Bool.self, forKey: .success)
    }
}

