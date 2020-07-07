//
//  HomeNewVC.swift
//  Streamly
//
//  Created by Rohit Singh on 7/3/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import UIKit

class HomeNewVC: UIViewController {

    // MARK: - Properties
     var channelList:EPGResponse?

    // MARK: - Outlets
    @IBOutlet weak var tableView:UITableView!
    @IBOutlet weak var collectionView:UICollectionView!

    // MARK: - LifeCycle
    override func viewDidLoad() {
        super.viewDidLoad()

        self.intialSetup()

        // Do any additional setup after loading the view.
    }

     // MARK: - Helper
     private func intialSetup()
     {
        self.tableView.dataSource = self
        self.tableView.delegate = self
        let programNib = UINib(nibName: "ProgramCollectionViewCell", bundle: Bundle.main)
        collectionView.register(programNib, forCellWithReuseIdentifier: "programCell")
        collectionView.delegate = self
        collectionView.dataSource = self
        let layout = EpgGridLayout()
                                 layout.channels = channelList
                                 layout.epgStartTime = NSDate(timeIntervalSince1970: 1594119600) as Date
                                 layout.epgEndTime = layout.epgStartTime.addingTimeInterval(layout.weekTimeInterval)
                                 layout.layoutInfo = NSMutableDictionary()
                                 layout.framesInfo = NSMutableDictionary()
                              collectionView.setCollectionViewLayout(layout, animated: true)
                   collectionView.reloadData()
                   self.tableView.reloadData()
                   self.collectionView.layoutIfNeeded()

     }

}

extension HomeNewVC : UITableViewDelegate, UITableViewDataSource {
    func numberOfSections(in tableView: UITableView) -> Int {
        return 1
    }

    func tableView(_ tableView: UITableView, heightForRowAt indexPath: IndexPath) -> CGFloat {
        return 100
    }

    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {

        return channelList?.data?.count ?? 0
    }
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        guard let cell =  tableView.dequeueReusableCell(withIdentifier: ChannelListCell.reuseIdentifier, for: indexPath) as? ChannelListCell else {return UITableViewCell()}
        cell.configureCell(channelList?.data?[indexPath.row].logo ?? "", channelList?.data?[indexPath.row].CallSign ?? "", item: channelList?.data?[indexPath.row].id ?? 0)
        return cell
    }
}

extension HomeNewVC : UICollectionViewDelegate, UICollectionViewDataSource {
    func numberOfSections(in collectionView: UICollectionView) -> Int {
        guard let channels = channelList?.data else { return 0}
        return channels.count
    }

    func collectionView(_ collectionView: UICollectionView, numberOfItemsInSection section: Int) -> Int {
        guard let channel = channelList?.data?[section], let programs = channel.programs else { return 0 }
        return programs.count
    }

    func collectionView(_ collectionView: UICollectionView, cellForItemAt indexPath: IndexPath) -> UICollectionViewCell {
        guard let programCell = collectionView.dequeueReusableCell(withReuseIdentifier: "programCell", for: indexPath) as? ProgramCollectionViewCell else {return UICollectionViewCell()}
//
//        programCell.layer.borderWidth = 1
//        programCell.layer.borderColor = UIColor.lightGray.cgColor

        guard let channel = channelList?.data?[indexPath.section], let programs = channel.programs else { return UICollectionViewCell() }

        programCell.configureWithModel(program: programs[indexPath.item])
        return programCell
     //   cell.programTIme.text = ""
//        let activityView = UIActivityIndicatorView(style: .large)
//           activityView.center = CGPoint(x: cell.contentView.frame.size.width / 2, y: cell.contentView.frame.size.height / 2)
//           cell.contentView.addSubview(activityView)
     //  cell.contentView.center = CGPoint(x: cell.contentView.frame.size.width / 2, y: cell.contentView.frame.size.height / 2)


    }

    func collectionView(_ collectionView: UICollectionView, didSelectItemAt indexPath: IndexPath) {
//        let ch = channels![indexPath.section]
//        let program = ch.programs![indexPath.row]
//        let message = "\(ch.channelName!) - \(program.name!)"
//        let alert = UIAlertController(title: "Alert", message: message, preferredStyle:.alert)
//        let alertAction = UIAlertAction(title: "Ok", style: .default) {  (alert:UIAlertAction) in
//            self.dismiss(animated: true, completion: nil)
//        }
//        alert.addAction(alertAction)
//        self .present(alert, animated: true, completion: nil)
    }
}

extension HomeNewVC : UIScrollViewDelegate {
    func scrollViewDidScroll(_ scrollView: UIScrollView) {
        if (scrollView == tableView) {
            collectionView.contentOffset = CGPoint(x: collectionView.contentOffset.x, y: tableView.contentOffset.y)
        }
        else {
            tableView.contentOffset = CGPoint(x: tableView.contentOffset.x, y: collectionView.contentOffset.y)
        }
    }
}
