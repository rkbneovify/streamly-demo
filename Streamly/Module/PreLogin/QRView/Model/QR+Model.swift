//
//  QR+Model.swift
//  Streamly
//
//  Created by Rohit Singh on 11/2/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import Foundation

struct QRResponse : Codable {

    let data : String?
    let success:Bool?
    let message:String?

    enum CodingKeys: String, CodingKey {

        case data = "data"
        case success = "success"
        case message = "message"

    }

    init(from decoder: Decoder) throws {
        let values = try decoder.container(keyedBy: CodingKeys.self)
        data = try values.decodeIfPresent(String.self, forKey: .data)
        success = try values.decodeIfPresent(Bool.self, forKey: .success)
        message = try values.decodeIfPresent(String.self, forKey: .message)

    }
}
